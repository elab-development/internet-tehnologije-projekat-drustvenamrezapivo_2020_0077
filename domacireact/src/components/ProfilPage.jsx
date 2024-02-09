import React from 'react'
import { useState ,useEffect} from 'react';
import axios from "axios";
import {Link} from 'react-router-dom';
import { Modal, Form } from 'react-bootstrap';
import { Button } from 'react-bootstrap';  //!!!!!
import { useParams } from 'react-router-dom';
import ButtonSeeProfile from './ButtonSeeProfile';
import ButtonFollow from './ButtonFollow';
import ButtonUnfollow from './ButtonUnfollow';
 
function ProfilPage({renderAll,setRenderAll}) {
 
  console.log("Ponovo izrenderovana profil page");

    const [user,setUser]=useState();
    const[friends,setFriends]=useState();
 
    const[azurirajProfile,setAzurirajProfile]=useState(false);
    
    let params=useParams();
 
    const [showModal, setShowModal] = useState(false);
    const handleShow = () => setShowModal(true);
    const handleClose = () => setShowModal(false);
 
 
    const [content, setContent] = useState('');
    const [file, setFile] = useState(null);
    const [location, setLocation] = useState('');
 
   
 
    // const handleContentChange = (e) => setContent(e.target.value);

    function handleContentChange(e){
      // console.log(e.target.value);
      setContent(e.target.value);
    }
    const handleFileChange = (e) => setFile(e.target.files[0]);
    const handleLocationChange = (e) => setLocation(e.target.value);
   
    useEffect(()=>{
      console.log("use effect profilPage");
    axios.get("/api/users/"+params.user_id,
     {
        headers: {
       
         
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
       
        },
      }
      ).then((response)=>{
     
        setUser(response.data.user);
       
 
           
      }).catch((error)=>{
          console.log(error);
      });
 
 
 
      axios.get("/api/friendships/"+window.sessionStorage.user_id,
     {
        headers: {
       
         
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
         
        },
      }
      ).then((response)=>{
     
        setFriends(response.data.friends);
 
 
           
      }).catch((error)=>{
          console.log(error);
      });
 
 
    },[params,azurirajProfile]);
 
 
 
   
 
    const handleAddPost = () => {
     
 
      if(!content || !file || !location){
       
      }else{
 
         
          const data=new FormData();
          data.append('user_id',window.sessionStorage.user_id);
          data.append('content',content);
          data.append('location',location);
          data.append('image',file);
         
          axios.post('api/posts/',data, {
            headers: {
              'Content-Type': 'multipart/form-data',
             
             'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
             
            },
          }).then((response)=>{
         
         
           
            setFile(null);
            setLocation('');
            setContent('');
       
         
       
          // render(user.numberOfPosts);
          setRenderAll(renderAll=>!renderAll);
   
         handleClose();
       
               
          }).catch((error)=>{
              console.log(error);
          })
 
 
      }
    };
 
  return (
    <section className="h-100 gradient-custom-2">
     
   
{user && window.sessionStorage.user_id!=user.user_id  ?
       <>
       <ButtonSeeProfile user_id={window.sessionStorage.user_id} name={"Return to your profile"}/>
      </>:  <></>}
     
  <div className="container py-5 h-100">
    <div className="row d-flex justify-content-center align-items-center h-100">
      <div className="col col-lg-9 col-xl-7">
        <div className="card">
          <div className="rounded-top text-white d-flex flex-row" style={{backgroundColor: "#000",height:"200px"}}>
            <div className="ms-4 mt-5 d-flex flex-column" style={{width: "150px"}}>
           
              <img src={user ? user.picture : ""}
             
                alt="Generic placeholder image" className="img-fluid img-thumbnail mt-4 mb-4"
                style={{width: "150px",height:"120px"}}/>
               
             
            </div>
            <div className="ms-3" style={{marginTop: "130px"}}>
              <h5>{user? user.name : ""}</h5>
              <h5>{user ? user.email : ""}</h5>
             
            </div>
          </div>
          <div className="p-4 text-black" style={{backgroundColor:"#f8f9fa"}}>
           
 
              {user && window.sessionStorage.user_id==user.user_id? <>
                <button type="button" className="btn btn-outline-dark bg-info" data-mdb-ripple-color="dark"
                style={{zIndex:"1"}}>
                 
               
                <Link to="/editprofile" className="nav-link">
                Edit profil
                </Link>
              </button>
 
                </> : <></>}
 
 
                <div>
      { user && window.sessionStorage.user_id==user.user_id? <> <Button variant="primary" onClick={handleShow}>
        Add post
      </Button>
 
      <Modal show={showModal} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Dodaj post</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>
            <Form.Group controlId="content">
              <Form.Label>Sadržaj</Form.Label>
              <Form.Control type="text" value={content} onChange={handleContentChange} />
              
            </Form.Group>
 
            <Form.Group controlId="file">
              <Form.Label>Fajl</Form.Label>
              <Form.Control type="file" onChange={handleFileChange} />
            </Form.Group>
 
            <Form.Group controlId="location">
              <Form.Label>Lokacija</Form.Label>
              <Form.Control type="text" value={location} onChange={handleLocationChange} />
            </Form.Group>
          </Form>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Zatvori
          </Button>
          <Button variant="primary" onClick={handleAddPost}>
            Dodaj post
          </Button>
        </Modal.Footer>
      </Modal></> : <></>}
     
    </div>
 
    
      {user && window.sessionStorage.user_id!=user.user_id && friends && !friends.some(friend => friend.user_id ==params.user_id)? <><ButtonFollow  azurirajProfile={azurirajProfile} setAzurirajProfile={setAzurirajProfile} pozicija={"profile"}user_id={user.user_id}/></> : <></>}
      {user && window.sessionStorage.user_id!=user.user_id && friends && friends.some(friend => friend.user_id ==params.user_id) ? <><ButtonUnfollow azurirajProfile={azurirajProfile} setAzurirajProfile={setAzurirajProfile} pozicija={"profile"} user_id={user.user_id}/></> : <></>}

 
 
            <div className="d-flex justify-content-end text-center py-1">
              <div>
                <p className="mb-1 h5">{user? user.numberOfPosts : ""}</p>
                <p className="small text-muted mb-0">Photos</p>
              </div>
              <div className="px-3">
                <p className="mb-1 h5">{user ? user.numberOfFriends : ""}</p>
                <p className="small text-muted mb-0">Friends</p>
              </div>
             
            </div>
          </div>
          <div className="card-body p-4 text-black">
            <div className="mb-5">
              <p className="lead fw-normal mb-1">{user? user.about!="null"? user.about : ""  : ""}</p>
             
            </div>
           
         
          </div>
        </div>
      </div>
      <>
        </>
    </div>
  </div>
</section>
  )
}
 
export default ProfilPage