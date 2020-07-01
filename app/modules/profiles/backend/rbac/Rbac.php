<?php
namespace modules\profiles\backend\rbac;

use Yii;
use yii\rbac\Item;
use yii\db\Query;
use yz\admin\models\User;

class Rbac
{
    const ROLE_ADMIN_ESTIMA = 'ADMIN_ESTIMA';
    const ROLE_ADMIN_LEGAL = 'ADMIN_LEGAL';


    /**
     * @return array
     */
    public static function dependencies()
    {
        return [
            self::ROLE_ADMIN_ESTIMA => ['Администратор', Item::TYPE_ROLE, []],
            self::ROLE_ADMIN_LEGAL=> ['Представитель Юрлица', Item::TYPE_ROLE, []],

        ];
    }

    /**
     * @return array
     */
    public static function getRolesList()
    {
        return [
            self::ROLE_ADMIN_ESTIMA => 'Администратор заказчика ',
            self::ROLE_ADMIN_LEGAL => 'Представитель Юрлица',
        ];
    }

    /**
     * @return array
     */
    public static function getAdminRoles()
    {
        return [
            self::ROLE_ADMIN_ESTIMA => self::ROLE_ADMIN_ESTIMA,
            self::ROLE_ADMIN_LEGAL => self::ROLE_ADMIN_LEGAL,
        ];
    }

    public static function getNewAdminRoles()
    {
        return [

        ];
    }

    public static function isNewAdminRoles()
    {
        $identity = Yii::$app->user->identity;

        if ($identity->is_super_admin) {
            return true;
        }
        $role = (new Query)
            ->select(['item_name'])
            ->from('{{%admin_auth_assignment}}')
            ->where(['user_id' => $identity->id])
            ->one();
        $currentRole =  $role['item_name'] ?? '';
        if(!$currentRole){
            return false;
        }
        if(in_array($currentRole, self::getNewAdminRoles())){
            return true;
        }
        return false;
    }

    public static function isRealNewAdminRoles()
    {
        $identity = Yii::$app->user->identity;


        if ($identity->is_super_admin) {
            return false;
        }
        $role = (new Query)
            ->select(['item_name'])
            ->from('{{%admin_auth_assignment}}')
            ->where(['user_id' => $identity->id])
            ->one();
        $currentRole =  $role['item_name'] ?? '';
        if(!$currentRole){
            return false;
        }

        return false;
    }


    /**
     * @param User $user
     * @return string
     */
    public static function getAdminRole(User $user)
    {
        $role = (new Query)
            ->select(['item_name'])
            ->from('{{%admin_auth_assignment}}')
            ->where(['user_id' => $user->id])
            ->one();

        return $role['item_name'] ?? '';
    }

    /**
     * @param $role
     * @param null $identity
     * @return bool
     */
    public function hasRole($role, $identity = null)
    {
        if ($identity === null) {
            $identity = Yii::$app->user->identity;
        }

        if ($identity instanceof User === false) {
            return false;
        }

        $roles = $identity->getRoles()
            ->select('name')
            ->indexBy('name')
            ->column();

        return isset($roles[$role]);
    }

    /**
     * @return bool
     */
    public static function isAdmin()
    {
        $identity = Yii::$app->user->identity;

        if ($identity instanceof User === false) {
            return false;
        }

        if ($identity->is_super_admin) {
            return true;
        }

        $adminRoles = self::getAdminRoles();
        $userRoles = $identity->getRoles()->select('name')->indexBy('name')->column();

        foreach ($userRoles as $role) {
            if (isset($adminRoles[$role])) {
                return true;
            }
        }

        return false;
    }

}