<?php

namespace jbrowneuk;

// Mocks
const MOCK_PROJECTS = [
    ['name' => 'proj1', 'description' => 'desc1', 'language' => 'php', 'license' => 'mocked', 'url' => 'http://localhost', 'archived' => false, 'updated' => 1024],
    ['name' => 'proj2', 'description' => 'desc2', 'language' => 'php', 'license' => 'mocked', 'url' => 'http://localhost', 'archived' => true, 'updated' => 2048]
];

function get_projects_from_github()
{
    return MOCK_PROJECTS;
}

require_once 'src/interfaces/irenderer.php';

require_once 'src/actions/projects.php';

describe('Projects Action', function () {
    beforeEach(function () {
        $this->assignCalls = array();
        $this->mockRenderer = \Mockery::spy(IRenderer::class);
        $this->mockRenderer->shouldReceive('assign')->andReturnUsing(function ($key, $val) {
            $this->assignCalls[$key] = $val;
        });

        $this->action = new Projects($this->mockRenderer);
        ($this->action)();
    });

    afterEach(function () {
        \Mockery::close();
    });

    it('should set page id', function () {
        $this->mockRenderer->shouldHaveReceived('setPageId')->with('projects')->once();
    });

    it('should display page on template', function () {
        $this->mockRenderer->shouldHaveReceived('displayPage')->with('projects')->once();
    });

    it('should assign project data', function () {
        expect($this->assignCalls['projects'])->toBe(MOCK_PROJECTS);
    });
});
