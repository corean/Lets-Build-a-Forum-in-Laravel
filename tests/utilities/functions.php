<?php

//factory(Thread::class)->make()

function make($class, $attribues = []) {
    return factory($class)->make($attribues);
}

function create($class, $attribues = []) {
    return factory($class)->create($attribues);
}
