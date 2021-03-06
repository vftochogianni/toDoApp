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
      <form onSubmit={(e) => {
        context.createTask(e, {name: task})
        setEditTaskId(false)
        setEditedTask('')
        setDeleteTask({})
        setDeleteDialogOpen(false)
      }}>
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
                <TextField error={context.error.length > 0} helperText={context.error} fullWidth={true} label='New task' value={task} onChange={(e) => {
                  setTask(e.target.value)
                  setEditTaskId(false)
                  setEditedTask('')
                  setDeleteTask({})
                  setDeleteDialogOpen(false)
                }}/>
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
                    <TextField error={context.editError.length > 0} helperText={context.editError} fullWidth={true} label='Update task' value={editedTask} onChange={(e) => setEditedTask(e.target.value)}/> :
                    task.name
                  }
                </TableCell>
                <TableCell align='right'>
                  {editTaskId === task.id ?
                    <div>
                      <IconButton aria-label="save" onClick={async () => {
                        const taskUpdated = await context.updateTask({...task, name: editedTask})
                        if (taskUpdated) {
                          setEditTaskId(false)
                          setEditedTask('')
                          setDeleteTask({})
                          setDeleteDialogOpen(false)
                          setTask('')
                        }
                      }}>
                        <SaveIcon/>
                      </IconButton>
                      <IconButton aria-label="cancel" color="secondary" onClick={() => {
                        setEditTaskId(false)
                        setEditedTask('')
                        setDeleteTask({})
                        setDeleteDialogOpen(false)
                        setTask('')
                      }}>
                        <CancelIcon />
                      </IconButton>
                    </div>
                    :
                    <div>
                      <IconButton aria-label="edit" onClick={() => {
                        setEditTaskId(task.id)
                        setEditedTask(task.name)
                        setDeleteTask({})
                        setDeleteDialogOpen(false)
                        setTask('')
                      }}>
                        <EditIcon/>
                      </IconButton>
                      <IconButton aria-label="complete" color="primary" onClick={async () => {
                        await context.completeTask(task)
                        setEditTaskId(false)
                        setEditedTask('')
                        setDeleteTask({})
                        setDeleteDialogOpen(false)
                        setTask('')
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
                    setEditTaskId(false)
                    setEditedTask('')
                    setTask('')
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
        setEditTaskId(false)
        setEditedTask('')
        setTask('')
      }} task={deleteTask.name} />

    </div>
  )
}

export default TaskTable