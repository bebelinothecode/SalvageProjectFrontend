import React, {createContext, useState, useContext} from "react";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
    const [isRegisteredTo, setIsRegisteredTo] = useState(false);
    const [isLoggedIn, setIsLoggedInTo] = useState(false);
    const [user, setUser] = useState(null);
  
    return (
      <AuthContext.Provider value={{ 
      isRegisteredTo, 
      setIsRegisteredTo,
      user, 
      setUser,
      isLoggedIn, 
      setIsLoggedInTo }}
      >
        {children}
      </AuthContext.Provider>
    );
  };
  
export const useAuth = () => useContext(AuthContext);