<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_homepage_redirects_to_the_public_report_form(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('lapor.form'));
    }
}
