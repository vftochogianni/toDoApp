import React from 'react'
import { Button, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle } from '@material-ui/core'
import PropTypes from 'prop-types'

function DeleteDialog (props) {
  const { isOpen, onCancel, onDelete, task } = props
  return (
    <Dialog fullWidth={true} maxWidth={"sm"} open={isOpen} onClose={onCancel}>
      <DialogTitle id="delete-dialog-title">
        Delete task "{task}"
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="delete-dialog-description">
          You are about to permanently delete the task "{task}". If you are sure for this action, proceed by pressing "DELETE".
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={onCancel}>Cancel</Button>
        <Button onClick={onDelete} autoFocus color='secondary'>
          DELETE
        </Button>
      </DialogActions>

    </Dialog>
  )
}

DeleteDialog.prototypes = {
  isOpen: PropTypes.bool,
  onCancel: PropTypes.func,
  onDelete: PropTypes.func,
  task: PropTypes.string
}

export default DeleteDialog
