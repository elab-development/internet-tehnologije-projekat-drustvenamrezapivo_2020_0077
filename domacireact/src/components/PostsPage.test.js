/*import React from 'react';
import { render, waitFor, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import PostsPage from './PostsPage'; // Adjust the import path as necessary
import axios from 'axios';
import { MemoryRouter, Route } from 'react-router-dom';

// Mock axios
jest.mock('axios');

// Helper function to render the component within a router
const renderWithRouter = (ui, { route = '/' } = {}) => {
  window.history.pushState({}, 'Test page', route);

  return render(ui, { wrapper: MemoryRouter });
};

const mockPostsData = {
    data: {
      posts: [
        {
          user_id: '1',
          post_id: '1',
          content: 'Test post content',
          created_at: '2021-01-01T00:00:00Z', // ISO 8601 format
          user: { user_id: '1' },
          likes: [],
          // Add other necessary fields
        }
      ]
    }
  };

describe('PostsPage', () => {
  beforeEach(() => {
    // Clear all mocks before each test
    jest.clearAllMocks();

    // Setup axios.get mock to return a promise with mock data
    axios.get.mockResolvedValue(mockPostsData);
  });

  it('fetches posts for friends when on /posts/:userId route', async () => {
    renderWithRouter(<PostsPage />, { route: '/posts/1' });

    await waitFor(() => {
      expect(axios.get).toHaveBeenCalledWith(expect.any(String), {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      });
    });

    // Check if the post content is displayed
    expect(screen.getByText(/Test post content/)).toBeInTheDocument();
  });

  it('changes page when pagination button is clicked', async () => {
    renderWithRouter(<PostsPage />, { route: '/posts/1' });
  
    // Simulate page change
    fireEvent.click(screen.getByText('2')); // Assuming '2' is the text on the pagination button for the next page
  
    
  });

  // Add more tests as needed for different routes and functionalities
});
*/