// Simple functional-style decorator

const Task = function(name) {
  this.name = name;
};

const urgent = function() {
  this.urgent = true;
  return this;
};

const finished = function() {
  this.finished = true;
  return this;
};

const decorate = (obj, ...decorators) => decorators.reduce((decorated, func) => func.call(decorated), obj);

const task1 = new Task('task 1');
const task2 = decorate(new Task('Task 2'), urgent);
const task3 = decorate(new Task('Task 3'), urgent, finished);

console.log(task1);
console.log(task2);
console.log(task3);
