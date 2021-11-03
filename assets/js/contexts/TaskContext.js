import React, { createContext } from 'react'
import axios from "axios";

export const TaskContext = createContext()

const defaultState = {
  tasks: [],
  error: '',
  editError: '',
  deleteError: '',
}

class TaskContextProvider extends React.Component {
  constructor (props) {
    super(props)
    this.state = defaultState
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
    axios.post('/tasks', { 'name': data.name }).then((res) => {
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
        ...defaultState,
        tasks: tasks,
      })
    }).catch((err) => {
      this.setState(
        {...this.state, error: err.response.data['error']}
      )
    })
  }

  //update
  async updateTask(data) {
    let tasks = this.state.tasks
    let task = tasks.find((task) => {
      return task.id === data.id
    })

    if (task.name === data.name) {
      return true
    }

    try {
      const res = await axios.put(`/tasks/${data.id}`, {'name': data.name})
      if (res.status !== 204) {
        this.setState(
          {...this.state, editError: res.data['error']}
        )

        return false
      }

      task.name = data.name

      this.setState({
        ...defaultState,
        tasks: tasks
      })

      return true
    } catch (e) {
      this.setState(
        {...this.state, editError: e.response.data['error']}
      )
      return false
    }
  }

  //complete
  async completeTask(data) {
    let tasks = this.state.tasks
    let task = tasks.find((task) => {
      return task.id === data.id
    })

    if (task.isCompleted) {
      return
    }

    try {
      const res = await axios.post(`/tasks/${data.id}/complete`)
      if (res.status !== 204) {
        return
      }

      task.isCompleted = true

      this.setState({
        ...defaultState,
        tasks: tasks,
      })
    } catch (e) {

    }
  }

  // delete
  async deleteTask(id) {
    let tasks = this.state.tasks
    let task = tasks.find((task) => {
      return task.id === id
    })

    try {
      const res = await axios.delete(`/tasks/${id}`, )
      if (res.status !== 204) {
        this.setState(
          {...this.state, deleteError: res.data['error']}
        )
      }

      tasks.splice(tasks.indexOf(task), 1)

      this.setState({
        ...defaultState,
        tasks: tasks,
      })
    } catch (e) {
      this.setState(
        {...this.state, deleteError: e.response.data['error']}
      )
    }
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
