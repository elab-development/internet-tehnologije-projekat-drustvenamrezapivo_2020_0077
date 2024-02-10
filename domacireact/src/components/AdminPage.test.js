import React from 'react';
import { render, waitFor, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import AdminPage from './AdminPage'; // Adjust the import path as necessary
import axios from 'axios';

// Automatically mock axios
jest.mock('axios');

describe('AdminPage Tests', () => {
  beforeEach(() => {
    // Clear all mock settings before each test
    axios.get.mockClear();
    axios.delete.mockClear();
  });

  test('renders admin page and fetches data', async () => {
    // Setup mock response for get request
    axios.get.mockResolvedValue({
        data: {
          posts: [
            { 
              user_id: '1', 
              post_id: '101', 
              content: 'Offensive post content', 
              created_at: '2020-01-01T00:00:00Z', 
              numberOfReports: 3 
            }
          ],
          comments: [
            { 
              user_id: '2', 
              post_id: '102', 
              comment_id: '201', 
              content: 'Offensive comment content', 
              created_at: '2020-01-02T00:00:00Z', 
              numberOfReports: 4 
            }
          ]
        }
      });
      

    render(<AdminPage />);

    // Verify that data is correctly fetched and rendered
    await waitFor(() => {
      expect(screen.getByText(/Offensive post content/)).toBeInTheDocument();
      expect(screen.getByText(/Offensive comment content/)).toBeInTheDocument();
    });
  });

  test('handles delete post functionality', async () => {
    // Assume there is one post and one comment in the mock response
    axios.get.mockResolvedValueOnce(/* same as above */);
    axios.delete.mockResolvedValue({});

    render(<AdminPage />);

    const offensivePostContent = await screen.findByText(/Offensive posts/);
    expect(offensivePostContent).toBeInTheDocument();

  
   
  });

  // Add more tests as needed...
});
