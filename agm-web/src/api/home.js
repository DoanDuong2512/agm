import apiClient from './base';

export const getMeetingConfig = (key) => {
  return apiClient.get(`/meeting-config`, { 
    params: { key: key } 
  });
};

  
  
  