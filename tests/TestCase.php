<?php

namespace NeoLikotsi\Payfast\Test;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load package service provider
     *
     * @param \Illuminate\Foundation\Application $app
     * @return NeoLikotsi\PayfastServiceProvider\PayfastServiceProvider
     */
    public function getPackageProviders($app)
    {
        return ['NeoLikotsi\PayfastServiceProvider'];
    }
}
