## Readonly methods in JavaScript

This is an experiment to mimic the behaviour of **const methods in C++**. 

When a function is called with the `callAsReadonly` extension method, it has read-only access to the instance (object properties become immutable). When an attempt is made to write to a property, an exception is thrown. 

### Usage
```javascript
<Function>.callAsReadonly(<Target>);

myObj.method.callAsReadonly(myObj);
```

### Exception handling
A custom exception handler can be registered globally that applies to all readonly calls.


### Example
```javascript
import registerCallAsReadonly from './readonly';

class App {
    constructor(name, version) {
        this.name = name;
        this.version = version;
    }
    incrementVersion() {
        this.version++;
    }
}

function myExceptionHandler(err) {
    console.error('Access Violation Exception. Cannot modify object with readonly access.');
}
registerCallAsReadonly(myExceptionHandler);

let app = new App('Const method test', 1);

// Incrementing version...
app.incrementVersion();
// Calling same method as const... An exception is thrown
app.incrementVersion.callAsConst(app);
```

See the [sample application](https://stackblitz.com/edit/react-7um1jz) in action.


### Implementation
```javascript
export default (exceptionHandler) => {
    if (typeof Function.prototype.callAsReadonly === 'undefined') {
        Function.prototype.callAsReadonly = function(obj) {
            //const self = {...obj};  // proposal; only works in Chrome at the moment
            const self = Object.assign({}, obj);
            
            try {
                return this.call(Object.freeze(self));
            } catch(e) {
                if (e instanceof TypeError) {
                    if (typeof exceptionHandler === 'function') exceptionHandler(e);
                    else throw TypeError(e);
                }
            }
        };
    }
};
```
