import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import axios from 'axios';
import ProfilPage from './ProfilPage';
import { useParams } from 'react-router-dom';
import { MemoryRouter } from 'react-router-dom';



jest.mock('axios');
jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'), 
  useParams: jest.fn(), 
}));
jest.mock('react-places-autocomplete', () => {
  return {
    __esModule: true,
    default: jest.fn(({ value, onChange }) => (
      <input type="text" value={value} onChange={(e) => onChange(e.target.value)} />
    )),
  };
});

useParams.mockReturnValue({ user_id: '123' });

describe('ProfilPage Component', () => {
  beforeEach(() => {
   
    jest.clearAllMocks();

   
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
const expectedNumberOfCalls = 2;
await waitFor(() => expect(axios.get).toHaveBeenCalledTimes(expectedNumberOfCalls));


 
    const addPostButton = await screen.findByText('Add post');
    fireEvent.click(addPostButton);
    const submitButton = screen.getByRole('button', { name: 'Dodaj post' });
    expect(submitButton).toBeInTheDocument();

   
    fireEvent.click(screen.getByText('Zatvori'));
   
  });
});