import './styles/app.css'
import './bootstrap'
import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { ThemeProvider } from '@material-ui/styles'

class ToDoApp extends Component {
  render () {
    return (
      <ThemeProvider>

      </ThemeProvider>
    )
  }
}

ReactDOM.render(<ToDoApp />, document.getElementById('root'))
