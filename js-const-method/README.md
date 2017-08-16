## Const methods in JavaScript

This is an experiment to mimic the behaviour of const methods in C++. 

When a function is called with the `callAsConst` extension method, it has read-only access to the instance (object properties become immutable). When an attempt is made to write to a property, an exception is thrown. 

### Usage
```javascript
<Function>.callAsConst(<Target>);

myObj.method.callAsConst(myObj);
```

### Implementation
```javascript
function registerCallAsConst(exceptionHandler) {
    if (typeof Function.prototype.callAsConst === 'undefined') {
        Function.prototype.callAsConst = function(obj) {
            //const self = {...obj}; // proposal, only works in Chrome
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
}
```

### Example
```javascript
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
    console.error('EXCEPTION: Access violation. Cannot modify object with readonly access.');
}

registerCallAsConst(myExceptionHandler);

let app = new App('Const method test', 1);

const c = m => console.log(m);
// Incrementing version...
app.incrementVersion();
// Calling same method as const... An exception is thrown
app.incrementVersion.callAsConst(app);
```

See [sample application](https://stackblitz.com/edit/react-3vzref?file=index.js) in action.

