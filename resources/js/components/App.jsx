import React from 'react';
import ReactDOM from 'react-dom';
import { HashRouter, Routes, Route } from 'react-router-dom';
import WelcomeScreen from '../screens/WelcomeScreen';
import LoginScreen from '../screens/LoginScreen';
import RegisterScreen from '../screens/RegisterScreen';

function App() {
    return (
       <HashRouter>
        <Routes>
            <Route path='/' element={<WelcomeScreen />} />
            <Route path='/login' element={<LoginScreen />} />
            <Route path='/new-register' element={<RegisterScreen />} />
        </Routes>
       </HashRouter>
    );
}

export default App;

if (document.getElementById('example')) {
    ReactDOM.render(<App />, document.getElementById('example'));
}
