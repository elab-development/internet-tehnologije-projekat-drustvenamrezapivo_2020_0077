import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import axios from 'axios';
import ProfilPage from './ProfilPage';
import { useParams } from 'react-router-dom';
import { MemoryRouter } from 'react-router-dom';



jest.mock('axios');
jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'), // Preserve other exports from react-router-dom
  useParams: jest.fn(), // Mock useParams
}));
jest.mock('react-places-autocomplete', () => {
  return {
    __esModule: true,
    default: jest.fn(({ value, onChange }) => (
      <input type="text" value={value} onChange={(e) => onChange(e.target.value)} />
    )),
  };
});
// Mock useParams to return a specific user_id
useParams.mockReturnValue({ user_id: '123' });

describe('ProfilPage Component', () => {
  beforeEach(() => {
    // Reset all mocks before each test
    jest.clearAllMocks();

    // Mocking useParams to return a specific user_id for the test
    useParams.mockReturnValue({ user_id: '123' });
  });

  it('renders and interacts with modal', async () => {
    axios.get.mockResolvedValue({
      data: {
        user: { /* Mock user data */ },
        friends: [ /* Mock friends data */ ]
      }
    });

  
render(
  <MemoryRouter>
    <ProfilPage />
  </MemoryRouter>
);
const expectedNumberOfCalls = 2; // adjust based on your component's behavior
await waitFor(() => expect(axios.get).toHaveBeenCalledTimes(expectedNumberOfCalls));


    // Interact with the modal
    const addPostButton = await screen.findByText('Add post');
    fireEvent.click(addPostButton);
    const submitButton = screen.getByRole('button', { name: 'Dodaj post' });
    expect(submitButton).toBeInTheDocument();

    // Close the modal and verify it's no longer visible
    fireEvent.click(screen.getByText('Zatvori'));
   
  });
});