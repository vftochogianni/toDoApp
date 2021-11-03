import './styles/app.css'
import './bootstrap'
import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import TaskContextProvider from './js/contexts/TaskContext'
import TaskTable from './js/components/TaskTable'

class ToDoApp extends Component {
  render () {
    return (
      <TaskContextProvider>
        <TaskTable />
      </TaskContextProvider>
    )
  }
}

ReactDOM.render(<ToDoApp />, document.getElementById('root'))
