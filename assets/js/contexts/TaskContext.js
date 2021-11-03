import React, { createContext } from 'react'
import axios from "axios";

export const TaskContext = createContext()

class TaskContextProvider extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      tasks: [],
      error: ''
    }
  }

  async componentDidMount() {
    this.setState({
      tasks: await this.getTasks()
    })
  }

  // get
  async getTasks() {
    const response = await axios.get('/tasks')
    return response.data
  }

  //create
  createTask(e, data) {
    e.preventDefault()
    const headers = {'Content-Type': 'application/json'};
    axios.post('/tasks', { 'name': data.name }, { headers}).then((res) => {
      if (res.status !== 201) {
        this.setState(
          {...this.state, error: res.data['error']}
        )
        return
      }
      const task = {...data, isCompleted: false, id: res.data['id']}

      let tasks = this.state.tasks
      tasks.push(task)
      this.setState({
        tasks: tasks,
        error: ''
      })
    }).catch((err) => {
      this.setState(
        {...this.state, error: err.response.data['error']}
      )
    })
  }

  //update
  updateTask(data) {
    let tasks = this.state.tasks
    let task = tasks.find((task) => {
      return task.id === data.id
    })

    task.name = data.name

    this.setState(
      {tasks: tasks}
    )
  }

  //complete
  completeTask(data) {
    let tasks = this.state.tasks
    let task = tasks.find((task) => {
      return task.id === data.id
    })

    task.isCompleted = true

    this.setState(
      {tasks: tasks}
    )
  }

  // delete
  deleteTask(id) {
    let tasks = [...this.state.tasks]
    let task = tasks.find((task) => {
      return task.id === id
    })

    tasks.splice(tasks.indexOf(task), 1)

    this.setState({ tasks: tasks })
  }

  render () {
    return (
      <TaskContext.Provider value={{
        ...this.state,
        createTask: this.createTask.bind(this),
        updateTask: this.updateTask.bind(this),
        deleteTask: this.deleteTask.bind(this),
        completeTask: this.completeTask.bind(this),
      }}>
        {this.props.children}
      </TaskContext.Provider>
    )
  }
}

export default TaskContextProvider
