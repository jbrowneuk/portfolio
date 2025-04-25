<?php

function renderAction($pdo, $renderer) {
    $renderer->setPageId('projects');
    $renderer->displayPage('projects');
}