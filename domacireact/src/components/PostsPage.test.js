import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import axios from 'axios';
import { useParams, useLocation } from 'react-router-dom';
import PostsPage from './PostsPage'; 
import { MemoryRouter } from 'react-router-dom';


jest.mock('axios');


jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'),
  useParams: jest.fn(),
  useLocation: jest.fn(),
}));

describe('PostsPage Component', () => {
    beforeEach(() => {
    
    window.sessionStorage.setItem('user_id', '123');
      useParams.mockReturnValue({ user_id: '123' }); 
      useLocation.mockReturnValue({ pathname: '/posts/123' });

      axios.get.mockResolvedValue(Promise.resolve({
        data: {
            posts: [ { user_id: "1",
            post_id: "1",
            image_path: "http://example.com/image.png",
            created_at: "2024-02-03T19:18:10.000000Z",
            location: "New York",
            likes: [
              {
                liker: {
                    user_id: "1",
                  
                  },
                user_id: "1",
                post_id: "1",
              }
            ],
            comments: [
              {
                comment_id: "1",
                post_id: "1",
                user_id: "1",
                content: "Great post!",
                commentator: {
                  user_id: "2",
                  name: "Jane Doe",
                }
              }
            ],
            user: {
              user_id: "1",
              name: "Ranko",
              email: "rankezis@gmail.com",
              picture: "http://example.com/user.png",
            }}]
           
        }
      }));
    });

    afterEach(() => {
        window.sessionStorage.clear();
      });
  
    it('renders without crashing', () => {
      render(<PostsPage />);
    });
  
    it('fetches and displays posts', async () => {
      
        
      
        const { findByText } = render(
            <MemoryRouter>
              <PostsPage />
            </MemoryRouter>
          );
      
        const postContent = await findByText('Location: New York');
        expect(postContent).toBeInTheDocument();
      });

      it('renders pagination controls and can change page', async () => {
        
      
        const { findByText, getByText } = render( <MemoryRouter>
            <PostsPage />
          </MemoryRouter>);
      
        
        const page1Content = await findByText('1');
        expect(page1Content).toBeInTheDocument();
      
      
        fireEvent.click(getByText('2'));
      
       
        const page2Content = await findByText('2');
        expect(page2Content).toBeInTheDocument();
      });

      it('filters posts based on input', async () => {
       
      
        const { findByText, getByPlaceholderText, getByText } = render(<MemoryRouter>
            <PostsPage />
          </MemoryRouter>);
      
     
   await waitFor(() => {
        fireEvent.change(screen.getByPlaceholderText('Filter posts with location'), { target: { value: 'New York' } });
      });
      
      fireEvent.click(getByText('Filter posts'));

        const filteredContent = await findByText('Location: New York');
        expect(filteredContent).toBeInTheDocument();
      });

  });