import Post from "./Post";
import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import axios from 'axios';

const mockPost = {
    user_id: "1",
    post_id: "1",
    image_path: "http://example.com/image.png",
    created_at: "2024-02-03T19:18:10.000000Z",
    location: "New York",
    likes: [
      {
        liker: {
            user_id: "1",
            // Other properties...
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
    }
  };

  jest.mock('axios');

  test('renders post component correctly', () => {
    render(
      <MemoryRouter>
        <Post post={mockPost} user_id="1" />
      </MemoryRouter>
    );
    expect(screen.getByText(/Details/)).toBeInTheDocument();
  });
  
  test('opens details modal on button click', () => {
    render(
      <MemoryRouter>
        <Post post={mockPost} user_id="1" />
      </MemoryRouter>
    );
    fireEvent.click(screen.getByText('Details'));

    expect(screen.getByText(/User:/)).toBeInTheDocument();
  });