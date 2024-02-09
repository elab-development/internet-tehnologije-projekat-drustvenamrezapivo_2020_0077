import React from 'react';
import { render, fireEvent, waitFor } from '@testing-library/react';
import ButtonDeletePost from './ButtonDeletePost';
import axios from 'axios';

jest.mock('axios');

describe('ButtonDeletePost', () => {
  it('triggers delete post action on click', async () => {
    const mockSetRenderAll = jest.fn();
    axios.delete.mockResolvedValue({});

    const { getByText } = render(<ButtonDeletePost user_id="1" post_id="1" setRenderAll={mockSetRenderAll} />);
    fireEvent.click(getByText(/Delete post/i));

    await waitFor(() => {
      expect(axios.delete).toHaveBeenCalledWith(expect.stringContaining('api/posts/1/1'), expect.anything());
      expect(mockSetRenderAll).toHaveBeenCalled();
    });
  });
});