import { Dropdown } from "flowbite-react";
import { HiCog, HiCurrencyDollar, HiLogout, HiViewGrid } from "react-icons/hi";
import React from 'react'

export const UserAvatarDropdown = ({firstName, lastName, onLogout}) => {
    const [isOpen, setIsOpen] = useState(false)
  return (
    <Dropdown label={`Welcome, ${firstName}`}>
      <Dropdown.Header>
        <span className="block text-sm">{`${firstName} ${lastName}`}</span>
        {/* <span className="block truncate text-sm font-medium">bonnie@flowbite.com</span> */}
      </Dropdown.Header>
      {/* <Dropdown.Item icon={HiViewGrid}>Dashboard</Dropdown.Item> */}
      <Dropdown.Item icon={HiCog}>Salvages</Dropdown.Item>
      <Dropdown.Item icon={HiCurrencyDollar}>Earnings</Dropdown.Item>
      <Dropdown.Divider />
      <Dropdown.Item icon={HiLogout}>Sign out</Dropdown.Item>
    </Dropdown>
  );
}

import React, { useState } from 'react';
import { Dropdown, Avatar } from 'flowbite-react';

export const UserAvatarDropdown = ({ firstName, lastName, onLogout }) => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleDropdown = () => {
    setIsOpen(!isOpen);
  };

  return (
    <div className="relative">
      <button onClick={toggleDropdown} className="flex items-center space-x-3">
        <Avatar
          alt="User Avatar"
          img="https://via.placeholder.com/150" // Replace with actual user avatar URL
          rounded={true}
        />
      </button>

      {isOpen && (
        <Dropdown
          placement="bottom-end"
          className="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md"
          onClick={toggleDropdown}
        >
          <Dropdown.Item>
            <span className="block px-4 py-2 text-sm text-gray-700">
              {firstName} {lastName}
            </span>
          </Dropdown.Item>
          <Dropdown.Item onClick={onLogout}>
            <span className="block px-4 py-2 text-sm text-red-600">Logout</span>
          </Dropdown.Item>
        </Dropdown>
      )}
    </div>
  );
};


