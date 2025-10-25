<?php

namespace Tuantq\LaravelPreventSpam\Middleware;

use Closure;
use Tuantq\LaravelPreventSpam\Honeypot;

class PreventSpam
{
    protected Honeypot $honeypot;

    public function __construct(Honeypot $honeypot)
    {
        $this->honeypot = $honeypot;
    }

    /**
     * Handles incoming requests and checks if the honeypot detects a bot.
     * If the honeypot detects a bot, it will abort the request.
     * Otherwise, it will allow the request to proceed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->honeypot->detect()) {
            return $this->abort();
        }

        return $next($request);
    }

    /**
     * Abort the request if the honeypot detects a bot.
     *
     * @return mixed
     */
    public function abort()
    {
        return $this->honeypot->abort();
    }
}
