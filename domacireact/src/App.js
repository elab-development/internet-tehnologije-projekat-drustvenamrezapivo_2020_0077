import logo from './logo.svg';
import './App.css';
import { Routes,Route} from 'react-router-dom';
import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';
import NavBar from './components/NavBar';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import ProfilPage
 from './components/ProfilPage';
 import PostsPage from './components/PostsPage';


function App() {
  const[token,setToken]=useState();
  const[ulogovani,setUlogovani]=useState();
  const[renderAll,setRenderAll]=useState(false);

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

  function logout(){
    axios.post('/api/logout',null,{
      headers:{
        'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
      },
    }).then((response)=>{
      window.sessionStorage.removeItem('auth_token');
    window.sessionStorage.removeItem('user');
    window.sessionStorage.removeItem('user_id');
    setToken();
  
    setUlogovani()

     navigate('/login');
    }).catch((e)=>{
      console.log(e);
    });
  }

  return (
   <Routes>
        <Route path="/login" element={<LoginPage addToken={addToken} />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path='/editprofile' element={<></>}/>
        <Route path='/' element={<NavBar logout={logout}/>}>
          
        <Route path='profile/:user_id' element={<><ProfilPage renderAll={renderAll} setRenderAll={setRenderAll}></ProfilPage><PostsPage  renderAll={renderAll} setRenderAll={setRenderAll} /></>}/>
        <Route path='posts/:user_id' element={<PostsPage  />}/>
        <Route path='explore/:user_id' element={<PostsPage  />}/>
        </Route>
      </Routes>
  );
}



export default App;
