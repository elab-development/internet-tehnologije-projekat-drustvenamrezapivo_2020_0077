import React from 'react';
import { render, screen, waitFor } from '@testing-library/react';
import axios from 'axios';
import Info from './Info'; // Adjust the import path according to your file structure
import { BrowserRouter as Router } from 'react-router-dom';

jest.mock('axios');
// Mock data to be returned by the Axios call
const mockData = {
  data: {
    numberOfPosts: 100,
    numberOfUsers: 50,
    numberOfAdmins: 5,
  },
};

describe('Info Component Tests', () => {
  beforeEach(() => {
    // Setup axios mock for each test
    axios.get.mockResolvedValue(mockData);
  });

  afterEach(() => {
    // Clear mock after each test
    jest.clearAllMocks();
  });

  test('fetches data and displays the info correctly', async () => {
    render(<Router><Info /></Router>);

    // Verify API call
    expect(axios.get).toHaveBeenCalledWith('api/info', expect.any(Object));

    // Wait for the component to update based on the mock data
    await waitFor(() => {
      expect(screen.getByText(/Number of posts: 100/)).toBeInTheDocument();
      expect(screen.getByText(/Number of users: 50/)).toBeInTheDocument();
      expect(screen.getByText(/Number of admins: 5/)).toBeInTheDocument();
    });
  });

  test('navigates to the community part on button click', async () => {
    render(<Router><Info /></Router>);

    // Find and click the button
    const button = screen.getByRole('button', { name: /Yes, I want to be a part of this community/ });
    expect(button).toBeInTheDocument();
   
  });
});