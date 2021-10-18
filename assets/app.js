import './styles/app.css'
import './bootstrap'
import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import ToDoContextProvider from './js/contexts/ToDoContext'
import ToDoTable from './js/components/ToDoTable'

class ToDoApp extends Component {
  render () {
    return (
      <ToDoContextProvider>
        <ToDoTable />
      </ToDoContextProvider>
    )
  }
}

ReactDOM.render(<ToDoApp />, document.getElementById('root'))
