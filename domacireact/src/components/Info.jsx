import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react';
import axios from 'axios';
import { Button } from 'react-bootstrap';
import { Link } from 'react-router-dom';
 
function Info() {
    const [numberOfPosts,setNumberOfPosts]=useState(0);
    const [numberOfUsers,setNumberOfUsers]=useState(0);
    const [numberOfAdmins,setNumberOfAdmins]=useState(0);
    useEffect(() => {
       
     
        axios
          .get('api/info', {
            headers: {
            //   'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
            },
          })
          .then((response) => {
         
           setNumberOfPosts(response.data.numberOfPosts);
           setNumberOfUsers(response.data.numberOfUsers);
           setNumberOfAdmins(response.data.numberOfAdmins);
          })
          .catch((error) => {
           
            console.log(error);
          });
      }, []);
 
 
      const containerStyles = {
        display: 'flex',
        flexDirection: 'column', // Postavljamo flexDirection na column
        alignItems: 'center', // Centriramo elemente po horizontali
        width: '80%',
        margin: 'auto',
        marginTop: '20px',
        padding: '20px',
        backgroundColor: '#f0f0f0',
        borderRadius: '10px',
    };
   
    const rowStyles = {
        display: 'flex',
        justifyContent: 'space-between', // Raspoređujemo elemente na krajeve reda
        width: '100%', // Širina reda je 100%
        marginBottom: '20px', // Dodajemo margin ispod reda
    };
   
    const infoStyles = {
        flex: '1', // Prva kolona zauzima fleksibilni prostor
        marginRight: '20px',
    };
   
    const descriptionStyles = {
        flex: '1', // Druga kolona zauzima fleksibilni prostor
    };
   
    const buttonStyles = {
        backgroundColor: '#98FB98',
        color: '#000000',
        fontSize: '20px',
        padding: '10px 20px',
        border: 'none',
        borderRadius: '5px',
        cursor: 'pointer',
    };
   
    return (
        <div style={containerStyles}>
            <div style={rowStyles}> {/* Red koji sadrži Quantity i Posibilities */}
                <div style={infoStyles}>
                    <h2>Quantity:</h2>
                    <p>Number of posts: {numberOfPosts}</p>
                    <p>Number of users: {numberOfUsers}</p>
                    <p>Number of admins: {numberOfAdmins}</p>
                </div>
   
                <div style={descriptionStyles}>
                    <h3>Posibilities:</h3>
                    <p>You can make new posts</p>
                    <p>You can become friend with other users</p>
                    <p>Will have possibility to like and comment posts</p>
                    <p>Will have possibility to see someone's profile and his posts</p>
                    <p>And in the end if you are frequently active, you can become an admin.</p>
                    <p>This will grant you the ability to manage the social network." </p>
                </div>
            </div>
   
            <Button style={buttonStyles}><Link to={`/login`} className="nav-link">Yes, I want to be a part of this community</Link></Button>
        </div>
    );
}
 
export default Info