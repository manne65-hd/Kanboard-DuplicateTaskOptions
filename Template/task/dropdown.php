<?php if ($this->projectRole->canUpdateTask($task)): ?>
<li>
    <?= $this->modal->small('object-group', t('Duplicate task with options ...'), 'DuplicateTaskOptionsController', 'copyOptions', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
</li>
<?php endif ?>
