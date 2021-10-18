import React, {useContext, useState} from 'react'
import { ToDoContext } from '../contexts/ToDoContext'
import {IconButton, Table, TableBody, TableCell, TableHead, TableRow, TextField} from '@material-ui/core'
import EditIcon from '@material-ui/icons/Edit'
import DeleteIcon from '@material-ui/icons/Delete'
import AddCircleIcon from '@material-ui/icons/AddCircle'
import SaveIcon from '@material-ui/icons/Save'
import CancelIcon from '@material-ui/icons/Cancel'
import DeleteDialog from './DeleteDialog'

function ToDoTable () {
  const context = useContext(ToDoContext)
  const [task, setTask] = useState('')
  const [editTaskId, setEditTaskId] = useState(false)
  const [editedTask, setEditedTask] = useState('')
  const [isDeleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [deleteTask, setDeleteTask] = useState({})

  return (
    <div>
      <form onSubmit={(e) => {context.createToDo(e, {task: task})}}>
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
                <TextField fullWidth={true} label='New task' value={task} onChange={(e) => setTask(e.target.value)}/>
              </TableCell>
              <TableCell align='right'>
                <IconButton aria-label="add"  color="primary" type='submit'>
                  <AddCircleIcon />
                </IconButton>
              </TableCell>
            </TableRow>
            {context.toDos.slice().reverse().map((toDo) => (
              <TableRow key={toDo.id}>
                <TableCell>
                  {editTaskId === toDo.id ?
                    <TextField fullWidth={true} label='Update task' value={editedTask} onChange={(e) => setEditedTask(e.target.value)}/> :
                    toDo.task
                  }
                </TableCell>
                <TableCell align='right'>
                  {editTaskId === toDo.id ?
                    <div>
                      <IconButton aria-label="save" onClick={() => {
                        context.updateToDo({id: toDo.id, task: editedTask})
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
                        setEditTaskId(toDo.id)
                        setEditedTask(toDo.task)
                      }}>
                        <EditIcon/>
                      </IconButton>
                      <IconButton aria-label="delete" color="secondary" onClick={() => {
                        setDeleteDialogOpen(true)
                        setDeleteTask(toDo)
                      }}>
                        <DeleteIcon />
                      </IconButton>
                    </div>
                  }
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </form>

      <DeleteDialog isOpen={isDeleteDialogOpen} onCancel={() => setDeleteDialogOpen(false)} onDelete={() => {
        context.deleteToDo(deleteTask.id)
        setDeleteDialogOpen(false)
        setDeleteTask({})
      }} task={deleteTask.task} />

    </div>
  )
}

export default ToDoTable