import React, { createContext } from 'react'

export const ToDoContext = createContext()

class ToDoContextProvider extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      toDos: [
        { id: 1, task: 'something 1' },
        { id: 2, task: 'something 2' },
        { id: 3, task: 'something 3' },
        { id: 4, task: 'something 3' },
        { id: 5, task: 'something 3' },
        { id: 6, task: 'something 3' },
      ]
    }
  }

  // get
  getToDo() {

  }

  //create
  createToDo(e, data) {
    e.preventDefault()
    let toDos = this.state.toDos
    toDos.push(data)
    this.setState(
      {toDos: toDos}
    )
  }

  //update
  updateToDo(data) {
    let toDos = this.state.toDos
    let toDo = toDos.find((toDo) => {
      return toDo.id === data.id
    })

    toDo.task = data.task

    this.setState(
      {toDos: toDos}
    )
  }

  // delete
  deleteToDo(id) {
    let toDos = [...this.state.toDos]
    let toDo = toDos.find((toDo) => {
      return toDo.id === id
    })

    toDos.splice(toDos.indexOf(toDo), 1)

    this.setState(
      {toDos: toDos}
    )
  }

  render () {
    return (
      <ToDoContext.Provider value={{
        ...this.state,
        createToDo: this.createToDo.bind(this),
        updateToDo: this.updateToDo.bind(this),
        deleteToDo: this.deleteToDo.bind(this),
      }}>
        {this.props.children}
      </ToDoContext.Provider>
    )
  }
}

export default ToDoContextProvider
