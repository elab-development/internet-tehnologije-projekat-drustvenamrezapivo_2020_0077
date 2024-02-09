import logo from './logo.svg';
import './App.css';

import { Routes,Route,Navigate} from 'react-router-dom';

import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';
import NavBar from './components/NavBar';

import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import ProfilPage
 from './components/ProfilPage';

 import PostsPage from './components/PostsPage';
import EditProfilPage from './components/EditProfilPage';
import Info from './components/Info';
import AdminPage from './components/AdminPage';

import RolePage from './components/RolePage';
import Statistics from './components/Statistics';


function App() {
  console.log("Izrenderovana app komponenta");

  

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
       JSON.stringify(user),
      
    );
    
    
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
    
  
    setUlogovani()

     navigate('/login');
    }).catch((e)=>{
      console.log(e);
    });
  }

  if (!window.sessionStorage.user_id) {
    return (
      <Routes>
        <Route path="/login" element={<LoginPage addToken={addToken} />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="/trial" element={<><Info/><PostsPage/></>}/>
        <Route path="*" element={<Navigate to="/login" />} />
      </Routes>
    );
  }else{
  return (
   <Routes>
   
     
      <Route path='/editprofile' element={<EditProfilPage setUlogovani={setUlogovani} ulogovani={ulogovani}/>}/>
      <Route path='/' element={<><NavBar logout={logout}/></>}>
        <Route
          index
          element={
            JSON.parse(window.sessionStorage.getItem('user')).role === "admin" ? <Statistics/> : <></>
          }
        />

 
        <Route path='profile/:user_id' element={<><ProfilPage renderAll={renderAll} setRenderAll={setRenderAll}></ProfilPage><PostsPage key={3}  renderAll={renderAll} setRenderAll={setRenderAll} /></>}/>
        <Route path='posts/:user_id' element={<PostsPage key={1}  />}/>
        <Route path='explore/:user_id' element={<PostsPage key={2}  />}/>

        {JSON.parse(window.sessionStorage.user).role=="admin"?  <Route path='adminview1' element={<AdminPage  />}/> : <></>}
        {JSON.parse(window.sessionStorage.user).role=="admin"?  <Route path='adminview2' element={<RolePage  />}/> : <></>}
        <Route path="*" element={<Navigate to="/" />} />
        
      </Route>
   
   
   </Routes>
 
  );
    }
}



export default App;
