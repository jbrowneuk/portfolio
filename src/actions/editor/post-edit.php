<?php

namespace jbrowneuk;

function normalizePostId(?string $input): string
{
    if (!is_string($input)) {
        return '';
    }

    return preg_replace('/[^a-z0-9]/', '-', mb_strtolower($input));
}

function getPostHasError(string $key): bool {
    return !array_key_exists($key, $_POST) || strlen(trim($_POST[$key])) === 0;
}

class PostEditor implements IEditorRoute
{
    const ROUTE_MODE_PARAM = 'deitor';
    const MODE_CREATE = 'new';
    const MODE_EDIT = 'edit';

    public function render(IPostsDBO $dbo, PortfolioRenderer $renderer, array $pageParams): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titleError = getPostHasError('title');
            $contentError = getPostHasError('content');
            $idError = getPostHasError('post_id');
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
            $mode = getValueFromPageParams($pageParams, self::ROUTE_MODE_PARAM);
            switch ($mode) {
                case self::MODE_EDIT:
                    $postId = getValueFromPageParams($pageParams, self::MODE_EDIT);
                    if ($postId !== null) {
                        $post = $dbo->getPost($postId);
                    }
                    break;

                case self::MODE_CREATE:
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
