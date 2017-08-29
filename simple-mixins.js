// Simple functional-style mixins

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

const mixer = (obj, ...mixins) => mixins.reduce((extended, func) => func.call(extended), obj);

const task1 = new Task('task 1');
const task2 = mixer(new Task('Task 2'), urgent);
const task3 = mixer(new Task('Task 3'), urgent, finished);

console.log(task1);
console.log(task2);
console.log(task3);
