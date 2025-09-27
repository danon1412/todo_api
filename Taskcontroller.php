<?php
class TaskController {
    private $taskModel;

    public function __construct() {
        $this->taskModel = new Task();
    }

    public function getAllTasks() {
        try {
            $tasks = $this->taskModel->getAll();
            Response::success($tasks, 'Tasks retrieved successfully');
        } catch (Exception $e) {
            Response::error('Failed to retrieve tasks: ' . $e->getMessage(), 500);
        }
    }

    public function getTask($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                Response::error('Invalid task ID', 400);
            }

            $task = $this->taskModel->getById($id);
            
            if (!$task) {
                Response::error('Task not found', 404);
            }
            
            Response::success($task, 'Task retrieved successfully');
        } catch (Exception $e) {
            Response::error('Failed to retrieve task: ' . $e->getMessage(), 500);
        }
    }

    public function createTask() {
        try {
            $input = $this->getInput();
            
            if (empty($input['title'])) {
                Response::error('Title is required', 400);
            }

            $data = [
                'title' => trim($input['title']),
                'description' => trim($input['description'] ?? ''),
                'status' => in_array($input['status'] ?? 'pending', ['pending', 'in_progress', 'completed']) 
                            ? $input['status'] 
                            : 'pending'
            ];

            $id = $this->taskModel->create($data);
            $task = $this->taskModel->getById($id);
            
            Response::success($task, 'Task created successfully', 201);
        } catch (Exception $e) {
            Response::error('Failed to create task: ' . $e->getMessage(), 500);
        }
    }

    public function updateTask($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                Response::error('Invalid task ID', 400);
            }

            $input = $this->getInput();
            
            if (empty($input['title'])) {
                Response::error('Title is required', 400);
            }

            $data = [
                'title' => trim($input['title']),
                'description' => trim($input['description'] ?? ''),
                'status' => in_array($input['status'] ?? 'pending', ['pending', 'in_progress', 'completed']) 
                            ? $input['status'] 
                            : 'pending'
            ];

            $this->taskModel->update($id, $data);
            $updatedTask = $this->taskModel->getById($id);
            
            Response::success($updatedTask, 'Task updated successfully');
        } catch (Exception $e) {
            Response::error('Failed to update task: ' . $e->getMessage(), 500);
        }
    }

    public function deleteTask($id) {
        try {
            if (!is_numeric($id) || $id <= 0) {
                Response::error('Invalid task ID', 400);
            }

            $this->taskModel->delete($id);
            Response::success(null, 'Task deleted successfully');
        } catch (Exception $e) {
            Response::error('Failed to delete task: ' . $e->getMessage(), 500);
        }
    }

    private function getInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Response::error('Invalid JSON input');
        }
        
        return $input ?? [];
    }
}