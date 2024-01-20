import React from 'react';
import Post from './Post';
import axios from 'axios';
import { useState } from 'react';
import { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useLocation } from 'react-router-dom';
import useWindowWidth from './useWindowWidth';
import { Form } from 'react-bootstrap';
import {Button} from 'react-bootstrap';


function PostsPage({renderAll,setRenderAll}) {
  console.log("postsPage render");
  const location = useLocation();
  const params=useParams();
  const currentWindowWidth = useWindowWidth();

  const [azurirajPosts, setAzurirajPosts] = useState(false);


  const[currentPosts,setCurrentPosts]=useState([]);
  const [allPosts, setAllPosts] = useState([]); 
  const [filteredPosts, setFilteredPosts] = useState([]); 

  const [filter, setFilter] = useState('');
   
  const [currentPage, setCurrentPage] = useState(1);
  const [postsPerPage] = useState(5);
 
  const indexOfLastPost = currentPage * postsPerPage;
  const indexOfFirstPost = indexOfLastPost - postsPerPage;
  
  const paginate = (pageNumber) => {
    setCurrentPage(pageNumber);
    const indexOfLastPost = pageNumber * postsPerPage;
    const indexOfFirstPost = indexOfLastPost - postsPerPage;
    setCurrentPosts(filteredPosts.slice(indexOfFirstPost, indexOfLastPost));
  };
  
  const handleFilterChange = (event) => {
    setFilter(event.target.value);
  };
 
  
  useEffect(() => {
    console.log("use effect postsPage");
    
    let putanja = '';
    console.log(params);
    console.log(location);
    if (location.pathname.startsWith('/posts/')) {
      putanja = 'api/postsOfFriends/';
    }
    if (location.pathname.startsWith('/explore/')) {
      putanja = 'api/postsOfEnemies/';
    }
    if (location.pathname.startsWith('/profile/')) {
      putanja = 'api/postsOfProfile/';
    }
  
    axios
      .get(putanja + params.user_id, {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      })
      .then((response) => {
        setAllPosts(response.data.posts);
        setFilteredPosts(response.data.posts); 
        setCurrentPosts(response.data.posts.slice(indexOfFirstPost, indexOfLastPost));
      })
      .catch((error) => {
        console.log(error);
      });
  }, [azurirajPosts,params]);

  useEffect(() => {
    
    
    const filtered = allPosts.filter((post) => post.location.includes(filter) || post.content.includes(filter));
    setFilteredPosts(filtered);
    setCurrentPage(1);
    setCurrentPosts(filtered.slice(0, postsPerPage)); 
  }, [filter, allPosts]);


 

  

  const postContainerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '20px',
    marginTop: '20px',
    alignItems: 'center',
  };

  const postStyle = {
    textAlign: 'center',
    marginBottom: '20px',
    width: '60%',
    padding: '20px',
    border: '1px solid #ccc',
    borderRadius: '8px',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
  };

  return (

    <div className="container" style={{ textAlign: 'center' }}>
     <Form>
      <Form.Group controlId="filter">
        <Form.Label>Filter:</Form.Label>
        <Form.Control
          type="text"
          placeholder="Unesite filter"
          value={filter}
          onChange={handleFilterChange}
          onKeyPress={(e) => {
            if (e.key === 'Enter') {
              e.preventDefault(); 
              handleFilterChange(e);
            }
          }}
        />
      </Form.Group>
    </Form>

    <p>Trenutna širina prozora: {currentWindowWidth}px</p>
   
    <h1>{location.pathname.startsWith('/explore') ? 'Posts of unfriends' : ''}</h1>
    <h1>{location.pathname.startsWith('/profile') ? 'Posts of profile' : ''}</h1>
    <h1>{location.pathname.startsWith('/posts') ? 'Posts of friends' : ''}</h1>
    <div style={postContainerStyle}>
      {currentPosts ? (
        currentPosts.map((post) => (
          <div key={`${post.user_id}_${post.post_id}`} style={postStyle}>
            <Post
              renderAll={renderAll}
              setRenderAll={setRenderAll}
              pozicija={'posts'}
              post={post}
              user_id={post.user.user_id}
              setAzurirajPosts={setAzurirajPosts}
              azurirajPosts={azurirajPosts}
            />
          </div>
        ))
      ) : (
        <></>
      )}
    </div>
    <div>
      {filteredPosts.length > postsPerPage && (
        <ul className="pagination">
          {Array.from({ length: Math.ceil(filteredPosts.length / postsPerPage) }).map((_, index) => (
            <li key={index} >
              <Button className={index + 1 == currentPage ? 'active' : ''}  onClick={() => paginate(index + 1)}>{index + 1}</Button>
            </li>
          ))}
        </ul>
      )}
    </div>
    
  </div>

    
  );
}

export default PostsPage;