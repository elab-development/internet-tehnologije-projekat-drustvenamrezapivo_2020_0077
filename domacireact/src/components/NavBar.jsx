import React from 'react'
import { Outlet } from "react-router-dom"; 
import {Link} from 'react-router-dom';
import { Button } from 'react-bootstrap';

function NavBar({logout}) {
   
  return (
    <>
    
    
    
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
  <div className="container-fluid">
    
    <p className='navbar-brand'>Beer social network</p>
    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span className="navbar-toggler-icon"></span>
    </button>
    <div className="collapse navbar-collapse" id="navbarText">
      <ul className="navbar-nav me-auto mb-2 mb-lg-0">
        <li className="nav-item">
        
          <Link to={`/posts/${window.sessionStorage.user_id}`} className="nav-link">
                Posts
        </Link>
        </li>
        <li className="nav-item">
         
        <Link to={`/profile/${window.sessionStorage.user_id}`} className="nav-link">
                MyProfile
        </Link>
        </li>
        <li className="nav-item">
          
        <Link to={`/explore/${window.sessionStorage.user_id}`} className="nav-link">
                Explore
        </Link>
        </li>


        {JSON.parse(window.sessionStorage.user).role=='admin'?<>
        <li className="nav-item">
          
          <Link to={`/adminview1`} className="nav-link">
                  Reported content
          </Link>
          </li>
        </> : <></>}


        <li className="nav-item">

      
        <Button onClick={(e)=>{logout(e)}} variant="primary">Logout</Button>
        </li>
      </ul>
      
    </div>
  </div>
  
</nav>
<Outlet/>

</>
  )
}

export default NavBar