import { Dropdown } from "flowbite-react";
import { HiCog, HiCurrencyDollar, HiLogout, HiViewGrid } from "react-icons/hi";
import React from 'react'

export const UserAvatarDropdown = ({firstName, lastName, onLogout}) => {
    // const [isOpen, setIsOpen] = useState(false)
  return (
    <Dropdown label={`Welcome, ${firstName}`}>
      <Dropdown.Header>
        <span className="block text-sm">{`${firstName} ${lastName}`}</span>
      </Dropdown.Header>
      <Dropdown.Item icon={HiCog}>My Bids</Dropdown.Item>
      <Dropdown.Divider />
      <Dropdown.Item icon={HiLogout}>Sign out</Dropdown.Item>
    </Dropdown>
  );
}