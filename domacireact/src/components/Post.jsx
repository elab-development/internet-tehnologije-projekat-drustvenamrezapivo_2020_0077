import React, { useState } from 'react';
import { Modal, Button,Row,Col } from 'react-bootstrap';
import axios from 'axios';
import ButtonSeeProfile from './ButtonSeeProfile';
import ButtonDeletePost from './ButtonDeletePost';
import ButtonFollow from './ButtonFollow';
import ButtonUnfollow from './ButtonUnfollow';

import { useLocation } from 'react-router-dom';



function Post({ post,user_id,pozicija,setAzurirajPosts,azurirajPosts,renderAll,setRenderAll}) {
  const location = useLocation();
  
 
  
  var trenutnaPutanja = window.location.pathname;

  const [showDetails, setShowDetails] = useState(false);
  const handleShowDetails = () => setShowDetails(true);
  const handleCloseDetails = () => setShowDetails(false);


  const[komentar,setKomentar]=useState("");


  function obrisiKomentar(e){
    const parsedObject = JSON.parse(e.target.value);
    let post_id=parsedObject.post_id;
    let user_id=parsedObject.user_id;
    let comment_id=parsedObject.comment_id;
  
  
    axios.delete('api/comments/'+user_id+"/"+post_id+"/"+comment_id, {
      headers: {
       
        
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
     
      },
    }).then((response)=>{
   
     
    setAzurirajPosts(azurirajPosts => !azurirajPosts);
    

          
    }).catch((error)=>{
        console.log(error);
    })


  }

  function dodajKomentar(e){
    const parsedObject = JSON.parse(e.target.value);
    
    let post_id=parsedObject.post_id;
    let user_id=parsedObject.user_id;

    

    
    const data=new FormData();
    data.append('user_id',user_id);
    data.append('post_id',post_id);
    data.append('commentator_id',window.sessionStorage.user_id);
    data.append('content',komentar);
    axios.post('api/comments/',data, {
      headers: {
       
        
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
       
      },
    }).then((response)=>{
    
    setAzurirajPosts(azurirajPosts => !azurirajPosts);
          
    }).catch((error)=>{
        console.log(error);
    })


  }
  function punjenje(e){
    console.log(e.target.value);
    setKomentar(e.target.value);
    

  }
  function like(e){
    const parsedObject = JSON.parse(e.target.value);
    
    let post_id=parsedObject.post_id;
    let user_id=parsedObject.user_id;
   

        
        const data=new FormData();
        data.append('user_id',user_id);
        data.append('post_id',post_id);
        data.append('liker_id',window.sessionStorage.user_id);
        
        axios.post('api/likes/',data, {
          headers: {
          
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
           
          },
        }).then((response)=>{
        
        
        setAzurirajPosts(azurirajPosts => !azurirajPosts);
        
              
        }).catch((error)=>{
            console.log(error);
        })

   
  }
  function unlike(e){
    const parsedObject = JSON.parse(e.target.value);
    
    let post_id=parsedObject.post_id;
    let user_id=parsedObject.user_id;
   
        axios.delete('api/likes/'+user_id+"/"+post_id+"/"+window.sessionStorage.user_id, {
          headers: {
           
            
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
           
          },
        }).then((response)=>{
      
         
         setAzurirajPosts(azurirajPosts => !azurirajPosts);
              
        }).catch((error)=>{
            console.log(error);
        })
  }

  function addReportComment(user_id,post_id,comment_id,reporter_id){
    console.log(user_id);
    console.log(post_id);
    console.log(comment_id);
    console.log(reporter_id);

   
   

        
        const data=new FormData();
        data.append('user_id',user_id);
        data.append('post_id',post_id);
        data.append('comment_id',comment_id);
        data.append('reporter_id',reporter_id);
        
        axios.post('api/commentreports/',data, {
          headers: {
          
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
           
          },
        }).then((response)=>{
        
        
        setAzurirajPosts(azurirajPosts => !azurirajPosts);
        
              
        }).catch((error)=>{
            console.log(error);
        })

   }
   function removeReportComment(user_id,post_id,comment_id,reporter_id){
    console.log(user_id);
    console.log(post_id);
    console.log(comment_id);
    console.log(reporter_id);

    axios.delete('api/commentreports/'+user_id+"/"+post_id+"/"+comment_id+"/"+reporter_id, {
      headers: {
       
        
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
     
      },
    }).then((response)=>{
   
     
    setAzurirajPosts(azurirajPosts => !azurirajPosts);
    

          
    }).catch((error)=>{
        console.log(error);
    })


    
   }
   function addReportPost(user_id,post_id,reporter_id){
    console.log(user_id);
    console.log(post_id);
    
    console.log(reporter_id);


    const data=new FormData();
    data.append('user_id',user_id);
    data.append('post_id',post_id);
    data.append('reporter_id',reporter_id);
    
    axios.post('api/postreports/',data, {
      headers: {
      
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
       
      },
    }).then((response)=>{
    
    
    setAzurirajPosts(azurirajPosts => !azurirajPosts);
    
          
    }).catch((error)=>{
        console.log(error);
    })


   }
   function removeReportPost(user_id,post_id,reporter_id){
    console.log(user_id);
    console.log(post_id);
    
    console.log(reporter_id);

    axios.delete('api/postreports/'+user_id+"/"+post_id+"/"+reporter_id, {
      headers: {
       
        
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
     
      },
    }).then((response)=>{
   
     
    setAzurirajPosts(azurirajPosts => !azurirajPosts);
    

          
    }).catch((error)=>{
        console.log(error);
    })

   }

  

  return (
    <div className='post-container' >
      
      <div className='post-info'>
        
      {location.pathname.startsWith('/explore/') || location.pathname.startsWith('/posts/')? <><ButtonSeeProfile user_id={post.user_id} name={post.user.name}/></>: <></>}
   
      {location.pathname.startsWith('/explore/')? <><ButtonFollow azurirajPosts={azurirajPosts} setAzurirajPosts={setAzurirajPosts}  pozicija={"posts"} user_id={post.user_id}/></>: ""}
      {location.pathname.startsWith('/posts/')? <><ButtonUnfollow azurirajPosts={azurirajPosts} setAzurirajPosts={setAzurirajPosts} pozicija={"posts"} user_id={post.user_id}/></> : ""}
       

      {!location.pathname.startsWith('/trial') && window.sessionStorage.user_id!=user_id &&  window.sessionStorage.user && JSON.parse(window.sessionStorage.user).role!='admin'
      && !post.reports.some(report=>report.reporter_id==window.sessionStorage.user_id)? <><Button onClick={(e)=>addReportPost(post.user_id,post.post_id,window.sessionStorage.user_id)} variant="danger">Report</Button></>: <></>}

      {!location.pathname.startsWith('/trial') && window.sessionStorage.user_id!=user_id && window.sessionStorage.user &&  JSON.parse(window.sessionStorage.user).role!='admin'
      && post.reports.some(report=>report.reporter_id==window.sessionStorage.user_id)? <><Button  onClick={(e)=>removeReportPost(post.user_id,post.post_id,window.sessionStorage.user_id)} variant="danger" >Unreport</Button></> : <></>}
        </div>
        
        <img src={post.image_path} style={{ width: '400px', height: '400px' }} />
       
 
      
 
      
    
      
    
        <div className="post-info">
        <p>{new Intl.DateTimeFormat('sr-RS', {
          year: 'numeric',
         month: 'numeric',
         day: 'numeric',
         hour: 'numeric',
         minute: 'numeric',
         hour12: false, // 24-satni format
         }).format(new Date(post.created_at))}</p>
           
          
          <p>{`Likes: ${post.likes.length}`}</p>
          <p>{`Comments: ${post.comments.length}`}</p>
          <p>{`Location: ${post.location}`}</p>
          {!location.pathname.startsWith('/trial')? <>
          <Button variant="primary" onClick={handleShowDetails}>
            Details
          </Button>
          </> : ""}

          {trenutnaPutanja!=='/explore' && trenutnaPutanja!=='/posts' && window.sessionStorage.user_id==user_id ?
 
   <ButtonDeletePost  renderAll={renderAll} setRenderAll={setRenderAll}   post_id={post.post_id} user_id={post.user_id} />
    : <></>}
<div className="
"></div>          
        </div>
     

      
      <Modal show={showDetails} onHide={handleCloseDetails} size="xl">
        <Modal.Header closeButton>
          <Modal.Title>User:{post.user.name} Location:{post.location} Content:{post.content}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          

        <Row>
  <Col sm={8}>
    
    <div>
      <p className="text-primary">Comments:</p>
      {post.comments.map((comment) => (
        <div key={comment.comment_id} className="mb-2">
          <Row>
            <Col sm={3}>
              
            <ButtonSeeProfile handleCloseDetails={handleCloseDetails} user_id={comment.commentator.user_id} name={comment.commentator.user_id==window.sessionStorage.user_id? "Me" :comment.commentator.name}></ButtonSeeProfile>
            
            </Col>
            
            <Col sm={9}>
  <p className="bg-light text-dark" style={{ maxWidth: '100%', wordWrap: 'break-word' }}>
    {comment.content}
  </p>
  {comment.commentator.user_id==window.sessionStorage.user_id ? <><button className="btn btn-danger" value={JSON.stringify({ post_id: post.post_id, user_id:post.user_id ,comment_id:comment.comment_id })} onClick={(e)=>{obrisiKomentar(e)}}>Delete</button></> : 
  <></>}
  {comment.commentator.user_id!=window.sessionStorage.user_id && window.sessionStorage.user && JSON.parse(window.sessionStorage.user).role!='admin'
 &&   !comment.reports.some(report=>report.reporter_id==window.sessionStorage.user_id) ?  <><Button onClick={(e)=>addReportComment(comment.user_id,comment.post_id,comment.comment_id,window.sessionStorage.user_id)} variant="danger">Report</Button></> : <></>}

{comment.commentator.user_id!=window.sessionStorage.user_id &&   window.sessionStorage.user && JSON.parse(window.sessionStorage.user).role!='admin'
 &&   comment.reports.some(report=>report.reporter_id==window.sessionStorage.user_id) ?  <><Button onClick={(e)=>removeReportComment(comment.user_id,comment.post_id,comment.comment_id,window.sessionStorage.user_id)} variant="danger">Unreport</Button></> : <></>}
</Col>
          </Row>
        </div>
      ))}
    </div>
  </Col>
  <Col>
   
    <div>
      <p className="text-success">Likes:</p>
      {post.likes.map((like) => (
        
       
        <ButtonSeeProfile  key={like.liker.user_id} handleCloseDetails={handleCloseDetails} user_id={like.liker.user_id} name={like.liker.user_id==window.sessionStorage.user_id? "Me" :like.liker.name}/>
        
      ))}
    </div>
  </Col>
</Row>
       



            
        </Modal.Body>
        <Modal.Footer>
        <Row className="w-100">
      <Col style={{ width: '80%' }}>
        <textarea onChange={(e)=>{punjenje(e)}}
          style={{ width: '100%', height: '100px' }}  
          placeholder="content of comment"
        />
      </Col>
      <Col>
        <Button value={JSON.stringify({ post_id: post.post_id, user_id: post.user_id })} onClick={(e)=>{dodajKomentar(e)}} variant="primary">Add comment</Button>
      </Col>
    </Row>
        
         <Row>
          <Button variant="secondary" onClick={handleCloseDetails}>
            Close
          </Button>
          

          {post.likes.some((like) => {
          return like.liker_id == window.sessionStorage.user_id;
           }) ? <Button className="btn btn-danger" value={JSON.stringify({ post_id: post.post_id, user_id: post.user_id })} variant="secondary" onClick={(e)=>{unlike(e)}}>
           Unlike
          
         </Button> : <Button variant="primary" value={JSON.stringify({ post_id: post.post_id, user_id: post.user_id })}  onClick={(e)=>{like(e)}}>
           Like
          
         </Button>}

        </Row>
         

        </Modal.Footer>
      </Modal>
    </div>
  );
}

export default Post;