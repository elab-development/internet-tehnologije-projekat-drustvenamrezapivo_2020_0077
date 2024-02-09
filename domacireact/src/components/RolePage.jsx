import React from 'react'
import { useState } from 'react';
import { useEffect } from 'react';
import axios from 'axios';
import { Button } from 'react-bootstrap';
function RolePage() {
   console.log("render role page");
   const [users,setUsers]=useState([]);
   const[pomocna,setPomocna]=useState(true);
    useEffect(() => {
        
      
        axios
          .get('api/mostActive', {
            headers: {
            //   'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
            },
          })
          .then((response) => {
          setUsers(response.data.users);
           
          })
          .catch((error) => {
            
            console.log(error);
          });
      }, [pomocna]);
      function setAdmin(user_id){
        
        axios
          .put('api/setAdmin/'+user_id, {
            headers: {
               'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
            },
          })
          .then((response) => {
            console.log(response);
          setPomocna(pomocna=>!pomocna);
           
          })
          .catch((error) => {
            
            console.log(error);
          });
      }
      const userContainerStyle = {
        border: '1px solid #ccc',
        padding: '10px',
        marginBottom: '10px',
        background:'white',
        borderRadius: '10px',
        marginTop:'10px',
      };
      
      const headingStyle = {
        marginBottom: '5px',
      };
      
      const paragraphStyle = {
        marginBottom: '10px',
      };
  return (
    <div className="container">
        <h1>The most active users this month</h1>
      {users.map((user) => (
        <div key={user.user_id} style={userContainerStyle}>
          <h3 style={headingStyle}>{user.name}</h3>
          <p style={paragraphStyle}>Email: {user.email}</p>
          {user.role=='admin'? 
          <Button variant="info">{user.user_id==window.sessionStorage.user_id? "Me" : "Already admin"} </Button>
          : <Button variant="primary" onClick={() => setAdmin(user.user_id)}>Give him/her admin</Button>}
          
        </div>
      ))}
    </div>

  )
}

export default RolePage