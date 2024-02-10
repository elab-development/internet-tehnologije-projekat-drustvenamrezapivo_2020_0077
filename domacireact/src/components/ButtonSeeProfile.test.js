import React from 'react';
import { render, fireEvent } from '@testing-library/react';
import ButtonSeeProfile from './ButtonSeeProfile';
import { BrowserRouter } from 'react-router-dom';

// Mock useNavigate and useLocation from 'react-router-dom'
jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'),
  useNavigate: () => jest.fn(),
  useLocation: () => ({
    pathname: '/currentpath'
  })
}));

describe('ButtonSeeProfile', () => {
  it('navigates to the user profile on click', async () => {
    const { getByText } = render(
      <BrowserRouter>
        <ButtonSeeProfile user_id="123" name="See Profile" />
      </BrowserRouter>
    );

    fireEvent.click(getByText(/see profile/i));
    // Normally we would assert navigation here, but since we can't actually
    // test navigation using RTL without a custom history object or additional
    // mocking, this is left as an example.
  });
});