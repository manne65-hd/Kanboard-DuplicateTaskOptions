<?php
// copied from /app/Controller/TaskDuplicationController.php
namespace Kanboard\Plugin\DuplicateTaskOptions\Controller;
use Kanboard\Controller\BaseController;
use Kanboard\Controller\TaskDuplicationController;

/**
 * Duplicate Task with options controller
 *
 * @package  Kanboard\plugins\DuplicateTaskOptions
 * @author   Frederic Guillot
 * @author   Manfred Hoffmann
 */
class  DuplicateTaskOptions extends TaskDuplicationController
{
    /**
     * Duplicate a task with options
     *
     * @access public
     */
    public function copyOptions()
    {
        $task = $this->getTask();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            list($valid, ) = $this->taskValidator->validateProjectModification($values);

            if ($valid) {
                $task_id = $this->taskProjectDuplicationModel->duplicateToProject(
                    $task['id'], $values['project_id'], $values['swimlane_id'],
                    $values['column_id'], $values['category_id'], $values['owner_id']
                );

                if ($task_id > 0) {
                    $this->flash->success(t('Task created successfully.'));
                    return $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $task['project_id'])), true);
                }
            }

            $this->flash->failure(t('Unable to create your task.'));
        }

        return $this->chooseOptions($task, 'task_duplication/copy_options');
    }

    /**
     * Choose options when duplicating task
     *
     * @access private
     * @param  array   $task
     * @param  string  $template
     */
    private function chooseOptions(array $task, $template)
    {
        $values = array();
        $projects_list = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        unset($projects_list[$task['project_id']]);

        if (! empty($projects_list)) {
            $dst_project_id = $this->request->getIntegerParam('dst_project_id', key($projects_list));

            $swimlanes_list = $this->swimlaneModel->getList($dst_project_id, false, true);
            $columns_list = $this->columnModel->getList($dst_project_id);
            $categories_list = $this->categoryModel->getList($dst_project_id);
            $users_list = $this->projectUserRoleModel->getAssignableUsersList($dst_project_id);

            $values = $this->taskDuplicationModel->checkDestinationProjectValues($task);
            $values['project_id'] = $dst_project_id;
        } else {
            $swimlanes_list = array();
            $columns_list = array();
            $categories_list = array();
            $users_list = array();
        }

        $this->response->html($this->template->render($template, array(
            'values' => $values,
            'task' => $task,
            'projects_list' => $projects_list,
            'swimlanes_list' => $swimlanes_list,
            'columns_list' => $columns_list,
            'categories_list' => $categories_list,
            'users_list' => $users_list,
        )));
    }
}
