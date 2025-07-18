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

require_once 'src/interfaces/iaction.php';

require_once 'src/core/renderer.php';

require_once 'src/actions/projects.php';

describe('Projects Action', function () {
    beforeEach(function () {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->assignCalls = array();
        $this->mockRenderer = $this->createMock(PortfolioRenderer::class);
        $this
            ->mockRenderer
            ->expects($this->any())
            ->method('assign')
            ->with()
            ->willReturnCallback(function ($key, $val) {
                $this->assignCalls[] = [$key, $val];
            });

        $this->action = new Projects();
    });

    it('should set page id', function () {
        $this->mockRenderer->expects($this->once())->method('setPageId')->with('projects');
        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should display page on template', function () {
        $this
            ->mockRenderer
            ->expects($this->atLeastOnce())
            ->method('displayPage')
            ->with('projects');

        $this->action->render($this->mockPdo, $this->mockRenderer, []);
    });

    it('should assign project data', function () {
        $expectedKey = 'projects';
        $this->action->render($this->mockPdo, $this->mockRenderer, []);

        $result = array_find($this->assignCalls, function ($value) use ($expectedKey) {
            return $value[0] === $expectedKey;
        });
        expect([$expectedKey, MOCK_PROJECTS])->toBe($result);
    });
});
