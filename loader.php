<?php

/**
 * Add applicable namespaces to the ProcessWire classLoader.
 */
wire('classLoader')->addNamespace('Rockett', __DIR__ . '/src/Rockett');
wire('classLoader')->addNamespace('Typographer', __DIR__ . '/src/Typographer');
