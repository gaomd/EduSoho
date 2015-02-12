<?php
namespace Topxia\Service\Thread\Impl;

use Topxia\Service\Thread\AbstractThreadFirewall;
use Topxia\Service\Common\ServiceKernel;

class ClassroomThreadFirewall extends AbstractThreadFirewall
{

    public function accessThreadRead($thread)
    {
        $user = $this->getCurrentUser();
        return $this->getClassroomService()->getClassroomMember($thread['targetId'], $user['id']);
    }

    public function accessThreadCreate($thread)
    {
        return $this->hasCreatePermission($thread);
    }

    public function accessThreadDelete($thread)
    {
        return $this->hasManagePermission($thread, true);
    }

    public function accessThreadUpdate($thread)
    {
        return $this->hasManagePermission($thread, true);
    }

    public function accessThreadStick($thread)
    {
        return $this->hasManagePermission($thread, false);
    }

    public function accessThreadNice($thread)
    {
        return $this->hasManagePermission($thread, false);
    }

    public function accessPostCreate($post)
    {
        return $this->hasCreatePermission($post);
    }

    public function accessPostUpdate($post)
    {
        return $this->hasManagePermission($thread, true);
    }

    public function accessPostDelete($post)
    {
        return $this->hasManagePermission($thread, true);
    }

    private function hasCreatePermission($resource)
    {
        return $this->getClassroomService()->canTakeClassroom($resource['targetId']);
    }

    private function hasManagePermission($resource, $ownerCanManage = false)
    {
        if ($this->getClassroomService()->canManageClassroom($resource['targetId'])) {
            return true;
        }

        $user = $this->getCurrentUser();
        if ($ownerCanManage && ($resource['userId'] == $user['id'])) {
            return true;
        }

        return false;
    }

    protected function getClassroomService()
    {
        return ServiceKernel::instance()->createService('Classroom:Classroom.ClassroomService');
    }

    protected function getKernel()
    {
        return ServiceKernel::instance();
    }

    public function getCurrentUser()
    {
        return $this->getKernel()->getCurrentUser();
    }

}