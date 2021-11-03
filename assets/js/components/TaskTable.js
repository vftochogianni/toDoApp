import React, {useContext, useState} from 'react'
import { TaskContext } from '../contexts/TaskContext'
import {IconButton, Table, TableBody, TableCell, TableHead, TableRow, TextField} from '@material-ui/core'
import EditIcon from '@material-ui/icons/Edit'
import DeleteIcon from '@material-ui/icons/Delete'
import AddCircleIcon from '@material-ui/icons/AddCircle'
import SaveIcon from '@material-ui/icons/Save'
import CancelIcon from '@material-ui/icons/Cancel'
import CheckCircleIcon from '@material-ui/icons/CheckCircle';
import DeleteDialog from './DeleteDialog'

function TaskTable () {
  const context = useContext(TaskContext)
  const [task, setTask] = useState('')
  const [editTaskId, setEditTaskId] = useState(false)
  const [editedTask, setEditedTask] = useState('')
  const [isDeleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [deleteTask, setDeleteTask] = useState({})

  return (
    <div>
      <form onSubmit={(e) => {context.createTask(e, {name: task})}}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell>Task</TableCell>
              <TableCell align='right'>Actions</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            <TableRow>
              <TableCell>
                <TextField error={context.error.length > 0} helperText={context.error} fullWidth={true} label='New task' value={task} onChange={(e) => setTask(e.target.value)}/>
              </TableCell>
              <TableCell align='right'>
                <IconButton aria-label="add"  color="primary" type='submit'>
                  <AddCircleIcon />
                </IconButton>
              </TableCell>
            </TableRow>
            {context.tasks.filter((task) => (!task.isCompleted)).slice().reverse().map((task) => (
              <TableRow key={task.id}>
                <TableCell>
                  {editTaskId === task.id ?
                    <TextField fullWidth={true} label='Update task' value={editedTask} onChange={(e) => setEditedTask(e.target.value)}/> :
                    task.name
                  }
                </TableCell>
                <TableCell align='right'>
                  {editTaskId === task.id ?
                    <div>
                      <IconButton aria-label="save" onClick={() => {
                        context.updateTask({id: task.id, name: editedTask})
                        setEditTaskId(false)
                      }}>
                        <SaveIcon/>
                      </IconButton>
                      <IconButton aria-label="cancel" color="secondary" onClick={() => {
                        setEditTaskId(false)
                      }}>
                        <CancelIcon />
                      </IconButton>
                    </div>
                    :
                    <div>
                      <IconButton aria-label="edit" onClick={() => {
                        setEditTaskId(task.id)
                        setEditedTask(task.name)
                      }}>
                        <EditIcon/>
                      </IconButton>
                      <IconButton aria-label="complete" color="primary" onClick={() => {
                        context.completeTask(task)
                      }}>
                        <CheckCircleIcon />
                      </IconButton>
                    </div>
                  }
                </TableCell>
              </TableRow>
            ))}

            {context.tasks.filter((task) => (task.isCompleted)).slice().reverse().map((task) => (
              <TableRow key={task.id}>
                <TableCell>
                  {editTaskId === task.id ?
                    <TextField fullWidth={true} label='Update task' value={editedTask} onChange={(e) => setEditedTask(e.target.value)}/> :
                    task.name
                  }
                </TableCell>
                <TableCell align='right'>
                  <IconButton aria-label="delete" color="secondary" onClick={() => {
                    setDeleteDialogOpen(true)
                    setDeleteTask(task)
                  }}>
                    <DeleteIcon />
                  </IconButton>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </form>

      <DeleteDialog isOpen={isDeleteDialogOpen} onCancel={() => setDeleteDialogOpen(false)} onDelete={() => {
        context.deleteTask(deleteTask.id)
        setDeleteDialogOpen(false)
        setDeleteTask({})
      }} task={deleteTask.task} />

    </div>
  )
}

export default TaskTable