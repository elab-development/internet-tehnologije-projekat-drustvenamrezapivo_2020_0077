import React from 'react'
import axios from 'axios';
import { useEffect } from 'react';
import { useState } from 'react';
import { Button } from 'react-bootstrap';

function AdminPage() {
    const [posts,setPosts]=useState([]);
    const[comments,setComments]=useState([]);
    const[pomocna,setPomocna]=useState(true);

    useEffect(() => {
        
      
        axios
          .get('/api/offensive', {
            headers: {
              'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
            },
          })
          .then((response) => {
            setPosts(response.data.posts);
            setComments(response.data.comments);
          })
          .catch((error) => {
            
            console.log(error);
          });
      }, [pomocna]);

      function handleDeleteComment(user_id,post_id,comment_id){
        
  
  
    axios.delete('api/comments/'+user_id+"/"+post_id+"/"+comment_id, {
      headers: {
       
        
       'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
     
      },
    }).then((response)=>{
   
     
    setPomocna(pomocna=>!pomocna);
    

          
    }).catch((error)=>{
        console.log(error);
    })
      }
      function handleDeletePost(user_id,post_id){
        
      
      
        axios.delete('api/posts/'+user_id+"/"+post_id, {
          headers: {
           
            
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
         
          },
        }).then((response)=>{
       
         
        setPomocna(pomocna=>!pomocna);
        
    
              
        }).catch((error)=>{
            console.log(error);
        })
    
      }
  return (
    <div className="container mt-5">
    <div className="row">
      {/* Kolona sa postovima */}
      <div className="col-md-6">
        <div className="bg-light p-4 rounded">
          <h2 className="text-center mb-4">Offensive posts</h2>
          {posts.map((post) => (
            <div key={`${post.user_id}_${post.post_id}`} className="mb-4 border p-3 rounded">
              <h6>{post.user_id+":"+post.post_id+" "+post.content+" "+new Intl.DateTimeFormat('sr-RS', {
          year: 'numeric',
         month: 'numeric',
         day: 'numeric',
         hour: 'numeric',
         minute: 'numeric',
         hour12: false, // 24-satni format
         }).format(new Date(post.created_at))}</h6>
              <div className="d-flex justify-content-between">
            {post.numberOfReports>2? <Button variant="danger" onClick={() => handleDeletePost(post.user_id, post.post_id)}>Delete Post</Button> : ""}
  
  <Button variant="info">Number of Reports: {post.numberOfReports}</Button>
</div>
            </div>
          ))}
        </div>
      </div>

      {/* Kolona sa komentarima */}
      <div className="col-md-6">
        <div className="bg-light p-4 rounded">
          <h2 className="text-center mb-4">Offensive comments</h2>
          {comments.map((comment) => (
            <div key={`${comment.user_id}_${comment.post_id}_${comment.comment_id}`} className="mb-4 border p-3 rounded">
              <h6>{comment.user_id+":"+comment.post_id+":"+comment.comment_id+" "+comment.content+" "+new Intl.DateTimeFormat('sr-RS', {
          year: 'numeric',
         month: 'numeric',
         day: 'numeric',
         hour: 'numeric',
         minute: 'numeric',
         hour12: false, // 24-satni format
         }).format(new Date(comment.created_at))}</h6>

<div className="d-flex justify-content-between">
    {comment.numberOfReports>2? <Button variant="danger" onClick={() => handleDeleteComment(comment.user_id, comment.post_id,comment.comment_id)}>Delete Comment</Button> : ""}
  
  <Button variant="info">Number of Reports: {comment.numberOfReports}</Button>
</div>
            </div>
          ))}
        </div>
      </div>
    </div>
  </div>
  )
}

export default AdminPage