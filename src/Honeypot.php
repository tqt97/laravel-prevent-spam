<?php

namespace Tuantq\LaravelPreventSpam;

use Illuminate\Http\Request;

class Honeypot
{
    protected static $abortHandler;

    protected Request $request;

    protected array $config;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * Checks if the honeypot is enabled.
     */
    public function enabled(): bool
    {
        return $this->config['enabled'];
    }

    /**
     * Detects if the form submission is a bot.
     *
     * If the honeypot is not enabled, it will return false.
     * If the submission is too quickly, it will return true.
     * If the request does not contain the honeypot field, it will return true.
     * If the honeypot field is not empty, it will return true.
     * Otherwise, it will return false.
     */
    public function detect(): bool
    {
        if (! $this->enabled()) {
            return false;
        }
        if ($this->isSubmittedTooQuickly()) {
            return true;
        }
        if (! $this->request->has($this->config['field_name'])) {
            return true;
        }
        if (! empty($this->request->input($this->config['field_name']))) {
            return true;
        }

        return false;
    }

    /**
     * Aborts the request if the honeypot detects a bot.
     *
     * If a custom abort handler is set, it will be called with the request as an argument.
     * Otherwise, it will return a 422 response with the message "Spam detected".
     *
     * @return mixed
     */
    public function abort()
    {
        if (static::$abortHandler) {
            return call_user_func(static::$abortHandler);
        }

        return abort(422, 'Spam detected');
    }

    /**
     * Checks if the form submission is too quickly.
     *
     * Compares the difference between the current time and the time stored in the honeypot field.
     * If the difference is less than the configured seconds, it returns true.
     */
    protected function isSubmittedTooQuickly(): bool
    {
        $startTime = (float) $this->request->input($this->config['field_time_name']);
        $elapsed = microtime(true) - $startTime;

        return $elapsed <= (float) $this->config['seconds'];
    }

    /**
     * Set a custom abort handler for the honeypot.
     *
     * The abort handler should be a callable that takes the request as an argument.
     * It will be called if the honeypot detects a bot.
     */
    public static function abortUsing(callable $handler): void
    {
        static::$abortHandler = $handler;
    }
}
