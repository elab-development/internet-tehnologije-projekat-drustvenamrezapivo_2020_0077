import logo from './logo.svg';
import './App.css';
import { Routes,Route} from 'react-router-dom';
import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';



function App() {
  const[token,setToken]=useState();
  const[ulogovani,setUlogovani]=useState();

  let navigate=useNavigate();

  function addToken(auth_token,user) {
    window.sessionStorage.setItem(
      "auth_token",
      auth_token
    );
    window.sessionStorage.setItem(
      "user_id",
      user.user_id
    );
    window.sessionStorage.setItem(
      "user",
       JSON.stringify(user)
      
    );
    setToken(auth_token);
    
    setUlogovani(user);
    
    navigate("/");
    
   
  }


  

  return (
   <Routes>
        <Route path="/login" element={<LoginPage addToken={addToken} />} />
        <Route path="/register" element={<RegisterPage />} />
       

        
      </Routes>
  );
}



export default App;
