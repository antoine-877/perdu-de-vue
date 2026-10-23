<?php

test('the home page redirects to the object list', function () {
    $response = $this->get('/');

    $response->assertRedirect('/objets');
});
