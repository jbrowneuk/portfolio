<?php

namespace jbrowneuk;

final class SUB_ACTIONS
{
    const EDIT_POST = 'post';

    const MODE_CREATE = 'new';
    const MODE_EDIT = 'edit';
}

function normalizePostId(string $input): string
{
    return preg_replace('/[^a-z0-9]/', '-', mb_strtolower($input));
}

class Editor implements IAction
{
    public function render(\PDO $pdo, PortfolioRenderer $renderer, array $pageParams): void
    {
        $auth = new Authentication($pdo);

        if (!$auth->isAuthenticated()) {
            $renderer->redirectTo('auth');
            return;
        }

        $subAction = '_default';
        if (isset($pageParams[0])) {
            $subAction = $pageParams[0];
        }

        $postsDBO = posts_dbo_factory($pdo);

        $renderer->setPageId('admin');

        switch ($subAction) {
            case SUB_ACTIONS::EDIT_POST:
                $this->renderPostEditor($postsDBO, $renderer, $pageParams);
                break;

            default:
                $renderer->displayPage('editor');
                break;
        }
    }

    private function renderPostEditor(IPostsDBO $dbo, PortfolioRenderer $renderer, array $pageParams): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titleError = !isset($_POST['title']);
            $contentError = !isset($_POST['content']);
            $idError = !isset($_POST['post_id']);
            $post = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'tags' => $_POST['tags'],
                'post_id' => normalizePostId($_POST['post_id'])
            ];

            if ($titleError || $contentError || $idError) {
                $renderer->assign('saveError', true);
                $renderer->assign('titleError', $titleError);
                $renderer->assign('contentError', $contentError);
                $renderer->assign('idError', $idError);
            } else {
                // Add or update post in DB
            }
        } else {
            $mode = getValueFromPageParams($pageParams, 'editor');
            switch ($mode) {
                case SUB_ACTIONS::MODE_EDIT:
                    $postId = getValueFromPageParams($pageParams, SUB_ACTIONS::MODE_EDIT);
                    if ($postId !== null) {
                        $post = $dbo->getPost($postId);
                    }
                    break;

                case SUB_ACTIONS::MODE_CREATE:
                    $post = null;
                    break;

                default:
                    $renderer->redirectTo('');
                    return;
            }
        }

        $renderer->assign('post', $post);
        $renderer->displayPage('post-editor');
    }
}
