import React from 'react';
import { render, fireEvent, waitFor, screen } from '@testing-library/react';
import axios from 'axios';
import LoginPage from './LoginPage';
import { BrowserRouter as Router } from 'react-router-dom';


test('on initial render', () =>{


render(  <Router>
    <LoginPage />
  </Router>);

jest.mock('axios');

screen.debug;



} )

test('displays error message when the email is invalid', async () => {
    render(
      <Router>
        <LoginPage />
      </Router>
    );
    fireEvent.change(screen.getByPlaceholderText('email address'), {
      target: { value: 'invalid-email' },
    });
    fireEvent.click(screen.getByText('Log in'));
    await waitFor(() => {
        expect(screen.getByText(/Pogresni kredencijali/i)).toBeInTheDocument();
      });
  });
